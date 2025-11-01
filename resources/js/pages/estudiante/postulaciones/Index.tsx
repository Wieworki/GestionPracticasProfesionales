import React, { useState } from 'react';
import EstudianteLayout from "@/layouts/dashboard/EstudianteLayout";
import ListadoPostulaciones from "@/pages/Postulaciones/ListadoPostulaciones";
import { Postulacion, columnasEstudiante } from '@/pages/Postulaciones/tablaColumns';

interface ListadoPostulacionesProps {
  nombre: string;
  postulaciones: any;
  filters: {
    search?: string;
  };
}

export default function PostulacionesOferta({ nombre, postulaciones, filters }: ListadoPostulacionesProps) {

  return (
    <EstudianteLayout nombre={nombre}>
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Postulaciones hechas
          </h1>
        </div>
        <ListadoPostulaciones
            postulaciones={postulaciones}
            columns={columnasEstudiante}
            ofertaId={null}
            filters={filters}
            searchRoute={route('estudiante.postulaciones.index')}
            goBackRoute={route('estudiante.dashboard')}
        ></ListadoPostulaciones>
      </div>
    </EstudianteLayout>
  );
};
