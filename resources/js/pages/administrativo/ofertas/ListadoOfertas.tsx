import React from 'react';
import { Head, router, Link } from '@inertiajs/react';
import { Oferta, columnasAdministrativo } from '@/pages/Oferta/tablaColumns';
import AdministrativoLayout from "@/layouts/dashboard/AdministrativoLayout";
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
  ofertas: PaginationData;
  filters: { search?: string };
  searchRoute: string;
}

export default function ListadoOfertas({ nombre, ofertas, filters }: Props) {

  return (
    <AdministrativoLayout nombre={nombre}>
      <Head title="Mis ofertas" />
      <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-semibold text-blue-700">
            Listado de ofertas
          </h1>
        </div>

        <TablaOfertas
            ofertas={ofertas}
            columns={columnasAdministrativo}
            filters={filters}
            searchRoute={route('administrativo.ofertas.index')}
            goBackRoute={route('administrativo.dashboard')}
        ></TablaOfertas>
      </div>
    </AdministrativoLayout>
  );
};