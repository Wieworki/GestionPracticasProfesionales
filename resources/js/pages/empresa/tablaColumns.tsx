import { ColumnDef } from '@tanstack/react-table';
import { Link } from '@inertiajs/react';

export interface Empresa {
  id: number;
  nombre: string;
  email: string;
  habilitado?: boolean;
  created_at: string;
}

export const columnasBase: ColumnDef<Empresa>[] = [
  {
    accessorKey: 'nombre',
    header: 'Nombre',
  },
  {
    accessorKey: 'email',
    header: 'Email',
  },
  {
    accessorKey: 'created_at',
    header: 'Fecha de registro',
    cell: ({ getValue }) => {
      const date = new Date(getValue() as string);
      return date.toLocaleDateString('es-AR');
    },
  },
];

export const columnasAdministrativo: ColumnDef<Empresa>[] = [
  ...columnasBase,
  {
    accessorKey: 'habilitado',
    header: 'Habilitado',
    cell: ({ getValue }) =>
      getValue() ? (
        <span className="text-green-600 font-medium">Sí</span>
      ) : (
        <span className="text-red-600 font-medium">No</span>
      ),
  },
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('administrativo.empresas.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
        <Link
          href={route('administrativo.empresas.edit', row.original.id)}
          className="text-green-600 hover:underline"
        >
          Editar
        </Link>
      </div>
    ),
  },
];

export const columnasEstudiante: ColumnDef<Empresa>[] = [
  ...columnasBase,
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <Link
        href={route('estudiante.empresas.show', row.original.id)}
        className="text-blue-600 hover:underline"
      >
        Ver
      </Link>
    ),
  },
];
