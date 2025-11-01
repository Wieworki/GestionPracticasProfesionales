import { ColumnDef } from '@tanstack/react-table';
import { Link } from '@inertiajs/react';

export interface Postulacion {
  id: number;
  oferta_id: number;
  empresa: string;
  titulo: string;
  estudiante: string;
  email_contacto: string;
  facultad_estudiante: string;
  fecha_creacion: string;
  estado: string;
  canBeSelected: string;
}

export const columnasBase: ColumnDef<Postulacion>[] = [
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
    accessorKey: 'estudiante',
    header: 'Estudiante',
  },
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
    accessorKey: 'estudiante',
    header: 'Estudiante',
  },
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
  {
    accessorKey: 'empresa',
    header: 'Empresa',
  },
  {
    accessorKey: 'titulo',
    header: 'Titulo oferta',
  },
  ...columnasBase,
  {
    id: 'acciones',
    header: 'Acciones',
    cell: ({ row }) => (
      <div className="flex gap-2">
        <Link
          href={route('estudiante.oferta.show', { id: row.original.oferta_id } )}
          className="text-blue-600 hover:underline"
        >
          Ver
        </Link>
      </div>
    ),
  },
];
