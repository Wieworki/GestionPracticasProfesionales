import React from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import { useReactTable, getCoreRowModel, flexRender, ColumnDef } from '@tanstack/react-table';
import { Button } from '@/components/ui/button';

interface Oferta {
  id: number;
  titulo: string;
  estado: string;
  fecha_creacion?: boolean;
  fecha_cierre: string;
  modalidad: string;
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginationData {
  data: Oferta[];
  links: PaginationLink[];
  current_page: number;
  last_page: number;
  total: number;
}

interface Props {
  ofertas: PaginationData;
  filters: {
    search?: string;
  };
  searchRoute: string;
}

export default function ListadoOfertas({
  ofertas,
  filters,
  searchRoute,
}: Props) {

  const [search, setSearch] = React.useState(filters.search || '');

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get(searchRoute, { search }, { preserveState: true });
  };

  const columns = React.useMemo(      // useMemo permite memorizar la definicion de columnas, para no recrearla en cada render
    () => [
      { header: 'Título', accessorKey: 'titulo' },      // accessorKey es el nombre del campo en cada objeto, TanStack usa esto para leer row.original[accessorKey]
      { header: 'Modalidad', accessorKey: 'modalidad' },
      { header: 'Fecha de creación', accessorKey: 'fecha_creacion' },
      { header: 'Fecha de cierre', accessorKey: 'fecha_cierre' },
      { header: 'Estado', accessorKey: 'estado' },
      {
        header: 'Acciones',
        cell: ({ row }: any) => (
          <Link
            href={route('empresa.ofertas.show', row.original.id)}   // row.original es el objeto original de la fila (la oferta)
            className="text-blue-600 hover:underline"
          >
            Ver detalle
          </Link>
        ),
      },
    ],
    []
  );

  const table = useReactTable({       // Crea la instancia de tabla de tanstack
    data: ofertas.data,
    columns,
    getCoreRowModel: getCoreRowModel(),   // Devuelve el modelo básico de filas, necesario para poder renderizarlas
  });

  return (
    <>


      <Head title="Mis Ofertas" />

      <div className="p-6 space-y-6">
        <div className="flex justify-between items-center mb-4">
          <h1 className="text-2xl font-bold">Mis Ofertas</h1>
          <form onSubmit={handleSearch} className="flex gap-2 w-full max-w-md">
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Buscar"
              className="border rounded-lg px-3 py-2 w-full"
            />
            <button
              type="submit"
              className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
              Buscar
            </button>
          </form>
        </div>
        <table className="min-w-full border border-gray-200 rounded-lg">
          <thead className="bg-gray-100">
            {table.getHeaderGroups().map(headerGroup => (
              <tr key={headerGroup.id}>
                {headerGroup.headers.map(header => (
                  <th key={header.id} className="px-4 py-2 text-left text-sm font-semibold">
                    {flexRender(header.column.columnDef.header, header.getContext())}     {/* Permite renderizar cabeceras y celdas */}
                  </th>
                ))}
              </tr>
            ))}
          </thead>
          <tbody>
            {table.getRowModel().rows.map(row => (
              <tr key={row.id} className="border-t">
                {row.getVisibleCells().map(cell => (
                  <td key={cell.id} className="px-4 py-2 text-sm">
                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>

        <div className="mt-4 flex justify-between">
          {ofertas.links?.map((link: any, i: number) => (
            <Link
              key={i}
              href={link.url || '#'}
              className={`px-3 py-1 text-sm border rounded ${
                link.active ? 'bg-blue-600 text-white' : 'text-gray-600'
              }`}
              dangerouslySetInnerHTML={{ __html: link.label }}
            />
          ))}
        </div>

        <div className="pt-4">
          <Link href={route('empresa.dashboard')}>
            <Button>Volver</Button>
          </Link>
        </div>
      </div>
    </>
  );
}
