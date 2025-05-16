<?php

namespace App\Dao;

use App\Events\MessageSent;
use App\Models\Orden;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdenDao
{
    public function create($data)
    {
        $orden = Orden::create($data);
        if ($data['detalles']) {
            $orden->productos()->sync($data['detalles']);
        }
        $message = [
            'user' => auth()->user()->name,
            'content' => request('message', 'Mensaje de prueba por defecto'),
            'time' => now()->format('H:i')
        ];
        event(new MessageSent($message));
        return $orden;
    }

    public function update($id, $data)
    {
        $orden = $this->find($id);
        if (isset($data['detalles'])) {
            $orden->productos()->sync($data['detalles']);
        }
        $orden->update($data);
        $message = [
            'user' => auth()->user()->name,
            'content' => request('message', 'Mensaje de prueba por defecto'),
            'time' => now()->format('H:i')
        ];
        event(new MessageSent($message));
        return $orden;
    }

    public function delete($id)
    {
        return Orden::where('id', $id)->delete();
    }

    public function find($id)
    {
        return Orden::with('productos')->find($id);
    }

    public function all()
    {
        return Orden::all();
    }

    public function allByEmpresa($id, $filter = null)
    {
        $buscar = "";
        if ($filter) {
            $buscar = $filter['buscar'] ?? "";
        }
        $orden = Orden::query();
        if ($buscar) {
            $orden = $orden->where('numero_factura', 'like', "%$buscar%");
        }
        $orden = $orden->where('empresa_id', $id)->orderBy('created_at', 'desc')->paginate($filter['page'], $columns = ['*'], $pageName = 'page');
        return ['data' => $orden->items(), 'total_pages' => $orden->lastPage(), 'page' => $orden->currentPage()];
    }

    public function getNumeroOrden() {
        $prefijo = 'FAC-' . now()->format('Ymd') . '-';

        do {
            $sufijo = strtoupper(Str::random(6));
            $numeroFactura = $prefijo . $sufijo;

            $existe = Orden::where('numero_factura', $numeroFactura)->exists();
        } while ($existe);

        return $numeroFactura;
    }

    public function allByDashboard($id, $filter = null) {

        /* total ventas */
        $total = Orden::where('empresa_id', $id)->where('status', 'pagado')->sum('total');

        /* total ventas por dia */
        $ventas = Orden::where('empresa_id', $id)->where('status', 'pagado')->whereDate('created_at', '=', date('Y-m-d'))->sum('total');

        /* CATEGORIAS */
        $ordenCategoria = Orden::join('detallle_ordens', 'ordens.id', '=', 'detallle_ordens.orden_id')
                            ->join('productos', 'detallle_ordens.producto_id', '=', 'productos.id')
                            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                            ->where('ordens.empresa_id', $id)->where('ordens.status', 'pagado')
                            ->select('categorias.id as id', 'categorias.nombre as category', DB::raw('SUM(detallle_ordens.precio * detallle_ordens.cantidad) as amount'))
                            ->groupBy('categorias.id', 'categorias.nombre')
                            ->get();
        $_categoriaDao = new CategoriaDao();
        $categorias = $_categoriaDao->allByCategoriaActivos($id);
        $categorias = $categorias->map(function ($categoria) use ($ordenCategoria) {
            $categoria->amount = $ordenCategoria->where('id', $categoria->id)->first()->amount ?? 0;
            return [
                'category' => $categoria->nombre,
                'amount' => $categoria->amount
            ];
        });

        /* venta por producto */
        $ordenProducto = Orden::join('detallle_ordens', 'ordens.id', '=', 'detallle_ordens.orden_id')
                            ->join('productos', 'detallle_ordens.producto_id', '=', 'productos.id')
                            ->where('ordens.empresa_id', $id)->where('ordens.status', 'pagado')
                            ->select('productos.id as id', 'productos.nombre as product', DB::raw('SUM(detallle_ordens.precio * detallle_ordens.cantidad) as amount'), DB::raw('SUM(detallle_ordens.cantidad) as cantidad'))
                            ->groupBy('productos.id', 'productos.nombre')
                            ->get();
        $_productoDao = new ProductoDao();
        $productos = $_productoDao->getAllByProductoEmpresa($id);
        $productos = $productos->map(function ($producto) use ($ordenProducto) {
            $producto->amount = $ordenProducto->where('id', $producto->id)->first()->amount ?? 0;
            $producto->cantidad = $ordenProducto->where('id', $producto->id)->first()->cantidad ?? 0;
            return [
                'name' => $producto->nombre,
                'revenue' => $producto->amount,
                'quantity' => $producto->cantidad
            ];
        });

        $productos = $productos->sortByDesc(function ($producto) {
            return $producto['revenue'];
        })->values();

        /* venta por dia de la semana */
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();
        $ventasPorDiaSemana = Orden::where('empresa_id', $id)
                                ->where('status', 'pagado')
                                ->whereBetween('created_at', [$inicioSemana, $finSemana])
                                ->select(
                                    DB::raw('DAYOFWEEK(created_at) as dia_numero'),
                                    DB::raw('SUM(total) as total_ventas'),
                                    DB::raw('COUNT(*) as cantidad_ordenes')
                                )
                                ->groupBy('dia_numero')
                                ->orderBy('dia_numero')
                                ->get();
        $diasSemana = [
            1 => 'Domingo',
            2 => 'Lunes',
            3 => 'Martes',
            4 => 'Miércoles',
            5 => 'Jueves',
            6 => 'Viernes',
            7 => 'Sábado'
        ];

        foreach ($diasSemana as $key => $dia) {
            $dataVentaSemanas[] = (object)[
                'dia_semana' => $dia,
                'dia_numero' => $key,
                'total_ventas' => $ventasPorDiaSemana->where('dia_numero', $key)->first()->total_ventas ?? 0,
                'cantidad_ordenes' => 0
            ];
        }
        
        // Ordenar por día de la semana (comenzando con Lunes)
        $dataVentaSemanas = collect($dataVentaSemanas);
        $dataVentaSemanas = $dataVentaSemanas->sortBy(function($item) {
            $orden = ['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6, 'Domingo' => 7];
            return $orden[$item->dia_semana];
        })->values();
        

        $dataVentaSemanas = $dataVentaSemanas->map(function($item) {
            return [$item->total_ventas];
        })->flatten();

        
        return ['totalRevenue' => $total, 'dailyAverage' => $ventas, 'categorySales' => $categorias, 'topProducts' => $productos, 'dailySales' => $dataVentaSemanas];
    }
}