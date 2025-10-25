import { ColumnDef } from '@tanstack/react-table';
import { Link } from '@inertiajs/react';

export interface Oferta {
  id: number;
  titulo: string;
  descripcion: string;
  fecha_cierre: string;
  fecha_creacion?: string;
  modalidad: string;
  estado: string;
  empresa: string;
}

export const columnasBase: ColumnDef<Oferta>[] = [
  {
    accessorKey: 'titulo',
    header: 'Titulo',
  },
  {
    accessorKey: 'modalidad',
    header: 'Modalidad',
  },
  {
    accessorKey: 'estado',
    header: 'Estado',
  },
  {
    accessorKey: 'fecha_cierre',
    header: 'Fecha de cierre',
    cell: ({ getValue }) => {
      const date = new Date(getValue() as string);
      return date.toLocaleDateString('es-AR');
    },
  },
];

export const columnasAdministrativo: ColumnDef<Oferta>[] = [
  ...columnasBase,
  {
    accessorKey: 'empresa',
    header: 'Empresa',
  },
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('administrativo.oferta.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];

export const columnasEmpresa: ColumnDef<Oferta>[] = [
  ...columnasBase,
  {
    accessorKey: 'fecha_creacion',
    header: 'Fecha de creación',
  },
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('empresa.ofertas.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];
