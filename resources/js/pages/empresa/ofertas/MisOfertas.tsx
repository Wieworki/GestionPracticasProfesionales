import React, { useState } from 'react';
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";
import TablaOfertas from "@/pages/Oferta/ListadoOfertas";

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
        <TablaOfertas
            ofertas={ofertas}
            filters={filters}
            searchRoute={route('empresa.ofertas.index')}
        ></TablaOfertas>
    </EmpresaLayout>
  );
};
