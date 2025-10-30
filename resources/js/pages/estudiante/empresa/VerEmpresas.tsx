import React from 'react';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import ListadoEmpresas from '@/pages/empresa/ListadoEmpresas';
import { columnasEstudiante } from '@/pages/empresa/tablaColumns';

interface Props {
  estudiante: {
    nombre: string;
    habilitado: boolean;
  };
  empresas: any;
  filters: {
    search?: string;
  };
}

export default function VerEmpresasEstudiante({
  estudiante,
  empresas,
  filters,
}: Props) {
  return (
    <EstudianteLayout nombre={estudiante.nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <h1 className="text-2xl font-semibold text-blue-700 mb-6">
          Empresas registradas
        </h1>

        <ListadoEmpresas
          empresas={empresas}
          filters={filters}
          columns={columnasEstudiante}
          searchRoute={route('estudiante.empresas.index')}
        />
      </div>
    </EstudianteLayout>
  );
}
