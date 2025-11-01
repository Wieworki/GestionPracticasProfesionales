import React from 'react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import ListadoEmpresas from '@/pages/empresa/ListadoEmpresas';
import { columnasAdministrativo } from '@/pages/empresa/tablaColumns';

interface Props {
  administrativo: {
    nombre: string;
    habilitado: boolean;
  };
  empresas: any;
  filters: {
    search?: string;
  };
  showNewButton: boolean;
}

export default function VerEmpresasAdmin({
  administrativo,
  empresas,
  filters,
  showNewButton,
}: Props) {
  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <h1 className="text-2xl font-semibold text-blue-700 mb-6">
          Listado de empresas
        </h1>

        <ListadoEmpresas
          empresas={empresas}
          filters={filters}
          columns={columnasAdministrativo}
          showNewButton={showNewButton}
          createRoute={route('administrativo.empresas.create')}
          searchRoute={route('administrativo.empresas.index')}
        />
      </div>
    </AdministrativoLayout>
  );
}
