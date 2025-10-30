import { ColumnDef } from '@tanstack/react-table';
import { Link } from '@inertiajs/react';

export interface Postulacion {
  id: number;
  id_oferta: number;
  empresa: string;
  titulo: string;
  estudiante: string;
  facultad_estudiante: string;
  fecha_creacion: string;
  estado: string;
  canBeSelected: string;
}

export const columnasBase: ColumnDef<Postulacion>[] = [
  {
    accessorKey: 'estudiante',
    header: 'Estudiante',
  },
  {
    accessorKey: 'estado',
    header: 'Estado de la postulacion',
  },
  {
    accessorKey: 'fecha_creacion',
    header: 'Fecha de la postulacion',
    cell: ({ getValue }) => {
      const date = new Date(getValue() as string);
      return date.toLocaleDateString('es-AR');
    },
  },
];

export const columnasAdministrativo: ColumnDef<Postulacion>[] = [
  ...columnasBase,
  {
    accessorKey: 'empresa',
    header: 'Empresa',
  },
  {
    accessorKey: 'titulo',
    header: 'Titulo oferta',
  },
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('administrativo.postulacion.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];

export const columnasEmpresa: ColumnDef<Postulacion>[] = [
  ...columnasBase,
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('empresa.postulacion.show', { postulacionId: row.original.id })}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];

export const columnasEstudiante: ColumnDef<Postulacion>[] = [
  ...columnasBase,
  {
    accessorKey: 'empresa',
    header: 'Empresa',
  },
  {
    accessorKey: 'titulo',
    header: 'Titulo oferta',
  },
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('estudiante.postulacion.show', row.original.id)}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];
