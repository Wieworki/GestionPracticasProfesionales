import React from 'react';
import { Head, router, Link } from '@inertiajs/react';
import { Oferta, columnasEstudiante } from '@/pages/Oferta/tablaColumns';
import EstudianteLayout from "@/layouts/dashboard/EstudianteLayout";
import TablaOfertas from "@/pages/Oferta/ListadoOfertas";

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginationData {
  data: Oferta[];
  links: PaginationLink[];
  current_page: number;
  last_page: number;
  total: number;
}

interface Props {
  nombre: string;
  nombreEmpresaFiltro: string;
  ofertas: PaginationData;
  filters: { search?: string };
  searchRoute: string;
}

export default function ListadoOfertas({ nombre, nombreEmpresaFiltro, ofertas, filters }: Props) {

  return (
    <EstudianteLayout nombre={nombre}>
      <Head title="Listado de ofertas" />
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Listado de ofertas 
            {nombreEmpresaFiltro && (
              <span>
                &nbsp; de {nombreEmpresaFiltro}
              </span>
            )}
          </h1>
        </div>

        <TablaOfertas
            ofertas={ofertas}
            columns={columnasEstudiante}
            filters={filters}
            searchRoute={route('estudiante.ofertas.index')}
            goBackRoute={route('estudiante.dashboard')}
        ></TablaOfertas>
      </div>
    </EstudianteLayout>
  );
};