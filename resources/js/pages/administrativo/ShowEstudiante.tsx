import React from 'react';
import { Link } from '@inertiajs/react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import { Button } from '@/components/ui/button';

interface Props {
  estudiante: {
    id: number;
    nombre: string;
    apellido: string;
    dni: string;
    telefono: string | null;
    email: string;
    fecha_registro: string;
    habilitado: boolean;
  };
  administrativo: {
    nombre: string;
  };
}

export default function ShowEstudiante({ estudiante, administrativo }: Props) {
  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        {/* --- Título --- */}
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Detalle del Estudiante
          </h1>
        </header>

        {/* --- Información principal --- */}
        <section className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-700">
          <Detail label="Nombre" value={estudiante.nombre} />
          <Detail label="Apellido" value={estudiante.apellido} />
          <Detail label="DNI" value={estudiante.dni} />
          <Detail label="Teléfono" value={estudiante.telefono || '—'} />
          <Detail label="Email" value={estudiante.email} />
          <Detail
            label="Fecha de registro"
            value={new Date(estudiante.fecha_registro).toLocaleDateString('es-AR')}
          />
          <Detail
            label="Estado"
            value={
              estudiante.habilitado ? (
                <span className="text-green-700 font-semibold">Habilitado</span>
              ) : (
                <span className="text-red-600 font-semibold">Deshabilitado</span>
              )
            }
          />
        </section>

        {/* --- Acciones --- */}
        <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">
          <Link href="/administrativo/estudiantes">
            <Button variant="secondary" className="w-full sm:w-auto">
              Volver
            </Button>
          </Link>

          <Link href={route('administrativo.estudiantes.edit', estudiante.id)}>
            <Button variant="default" className="w-full sm:w-auto">
              Editar
            </Button>
          </Link>
        </footer>
      </div>
    </AdministrativoLayout>
  );
}

/* --- Componente auxiliar compacto --- */
function Detail({ label, value }: { label: string; value: React.ReactNode }) {
  return (
    <div className="flex flex-col">
      <span className="text-xs text-gray-500">{label}</span>
      <span className="font-medium text-gray-900 text-sm truncate">{value}</span>
    </div>
  );
}
