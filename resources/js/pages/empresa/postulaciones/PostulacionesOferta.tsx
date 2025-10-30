import React, { useState } from 'react';
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";
import ListadoPostulaciones from "@/pages/Postulaciones/ListadoPostulaciones";
import { Postulacion, columnasEmpresa } from '@/pages/Postulaciones/tablaColumns';

interface ListadoPostulacionesProps {
  nombre: string;
  ofertaId: number;
  tituloOferta: string;
  postulaciones: any;
  filters: {
    search?: string;
  };
}

export default function PostulacionesOferta({ nombre, ofertaId, tituloOferta, postulaciones, filters }: ListadoPostulacionesProps) {

  return (
    <EmpresaLayout nombre={nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Postulaciones recibidas para {tituloOferta}
          </h1>
        </div>
        <ListadoPostulaciones
            postulaciones={postulaciones}
            columns={columnasEmpresa}
            ofertaId={ofertaId}
            filters={filters}
            searchRoute={route('empresa.ofertas.postulantes')}
            goBackRoute={route('empresa.ofertas.index')}
        ></ListadoPostulaciones>
      </div>
    </EmpresaLayout>
  );
};
