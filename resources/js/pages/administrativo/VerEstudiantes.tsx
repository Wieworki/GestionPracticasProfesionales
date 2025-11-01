import React from 'react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import ListadoEstudiantes from '@/pages/estudiante/ListadoEstudiantes';
import { columnasAdministrativo } from '@/pages/estudiante/tablaColumns';

interface Props {
  administrativo: {
    nombre: string;
    habilitado: boolean;
  };
  estudiantes: any;
  filters: {
    search?: string;
  };
}

export default function VerEstudiantesAdmininistrativo({
  administrativo,
  estudiantes,
  filters,
}: Props) {
  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Listado de estudiantes
          </h1>
        </div>

        <ListadoEstudiantes
          estudiantes={estudiantes}
          filters={filters}
          columns={columnasAdministrativo}
          searchRoute={route('administrativo.estudiantes.index')}
          createRoute={route('administrativo.estudiantes.create')}
        />
      </div>
    </AdministrativoLayout>
  );
}
