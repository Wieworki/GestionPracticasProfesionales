import { ColumnDef } from '@tanstack/react-table';
import { Link } from '@inertiajs/react';

export interface Estudiante {
  id: number;
  dni: string;
  nombre: string;
  email: string;
  habilitado?: boolean;
  created_at: string;
}

export const columnasBase: ColumnDef<Estudiante>[] = [
  {
    accessorKey: 'nombre',
    header: 'Nombre',
  },
  {
    accessorKey: 'dni',
    header: 'DNI',
  },
  {
    accessorKey: 'email',
    header: 'Email de contacto',
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

export const columnasAdministrativo: ColumnDef<Estudiante>[] = [
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
          href={route('administrativo.estudiantes.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];

