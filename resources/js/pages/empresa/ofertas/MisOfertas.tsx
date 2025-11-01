import React, { useState } from 'react';
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";
import TablaOfertas from "@/pages/Oferta/ListadoOfertas";
import { Oferta, columnasEmpresa } from '@/pages/Oferta/tablaColumns';

interface ListadoOfertasProps {
  empresa: {
      nombre: string;
  };
  ofertas: any;
  filters: {
    search?: string;
  };
}

export default function ListadoOfertas({ empresa, ofertas, filters }: ListadoOfertasProps) {

  return (
    <EmpresaLayout nombre={empresa.nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Mis ofertas
          </h1>
        </div>
        <TablaOfertas
            ofertas={ofertas}
            columns={columnasEmpresa}
            filters={filters}
            searchRoute={route('empresa.ofertas.index')}
            goBackRoute={route('empresa.dashboard')}
        ></TablaOfertas>
      </div>
    </EmpresaLayout>
  );
};
