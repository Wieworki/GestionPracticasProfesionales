// resources/js/pages/empresa/ListadoEmpresas.tsx
import React from 'react';
import {
  useReactTable,
  getCoreRowModel,
  flexRender,
  ColumnDef,
} from '@tanstack/react-table';
import { router, Link } from '@inertiajs/react';
import type { Empresa } from './tablaColumns';

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginationData {
  data: Empresa[];
  links: PaginationLink[];
  current_page: number;
  last_page: number;
  total: number;
}

interface Props {
  empresas: PaginationData;
  filters: {
    search?: string;
  };
  columns: ColumnDef<Empresa, any>[];
  showNewButton?: boolean;
  createRoute?: string;
  searchRoute: string;
}

export default function ListadoEmpresas({
  empresas,
  filters,
  columns,
  showNewButton = false,
  createRoute,
  searchRoute,
}: Props) {
  const [search, setSearch] = React.useState(filters.search || '');

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get(searchRoute, { search }, { preserveState: true });
  };

  const table = useReactTable({
    data: empresas.data,
    columns,
    getCoreRowModel: getCoreRowModel(),
  });

  return (
    <div>
      {/* Header y buscador */}
      <div className="flex justify-between items-center mb-4">
        <form onSubmit={handleSearch} className="flex gap-2 w-full max-w-md">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Buscar por nombre, email o CUIT..."
            className="border rounded-lg px-3 py-2 w-full"
          />
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          >
            Buscar
          </button>
        </form>

        {showNewButton && createRoute && (
          <Link
            href={createRoute}
            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Nueva empresa
          </Link>
        )}
      </div>

      {/* Tabla */}
      <div className="overflow-x-auto rounded-lg border border-gray-200">
        <table className="w-full border-collapse">
          <thead className="bg-gray-50">
            {table.getHeaderGroups().map((headerGroup) => (
              <tr key={headerGroup.id}>
                {headerGroup.headers.map((header) => (
                  <th
                    key={header.id}
                    className="text-left px-4 py-2 text-sm font-semibold text-gray-600"
                  >
                    {flexRender(header.column.columnDef.header, header.getContext())}
                  </th>
                ))}
              </tr>
            ))}
          </thead>
          <tbody>
            {table.getRowModel().rows.length > 0 ? (
              table.getRowModel().rows.map((row) => (
                <tr key={row.id} className="border-t hover:bg-gray-50 transition">
                  {row.getVisibleCells().map((cell) => (
                    <td key={cell.id} className="px-4 py-2 text-sm text-gray-700">
                      {flexRender(cell.column.columnDef.cell, cell.getContext())}
                    </td>
                  ))}
                </tr>
              ))
            ) : (
              <tr>
                <td
                  colSpan={columns.length}
                  className="text-center py-4 text-gray-500"
                >
                  No se encontraron resultados.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Paginación */}
      <div className="mt-6 flex justify-center space-x-1">
        {empresas.links.map((link, index) => (
          <button
            key={index}
            disabled={!link.url}
            onClick={() =>
              link.url && router.visit(link.url, { preserveState: true })
            }
            className={`px-3 py-1 rounded ${
              link.active
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
            dangerouslySetInnerHTML={{ __html: link.label }}
          />
        ))}
      </div>
    </div>
  );
}
