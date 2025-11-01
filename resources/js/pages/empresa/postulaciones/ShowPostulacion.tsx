import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import EmpresaLayout from '@/layouts/dashboard/EmpresaLayout';
import { Button } from '@/components/ui/button';
import { Postulacion } from '@/pages/Postulaciones/tablaColumns';

interface Props {
  nombre: string;
  postulacion: Postulacion;
}

export default function ShowPostulacion({ nombre, postulacion }: Props) {
  const { patch, processing } = useForm({});

  const handleSelection = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que desea seleccionar este postulante?')) {
      patch(route('empresa.postulacion.seleccionarPostulante', { postulacionId: postulacion.id }));
    }
  };

  return (
    <EmpresaLayout nombre={nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        {/* Título */}
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Detalle de la postulacion a {postulacion.titulo}
          </h1>
        </header>

        {/* Información principal */}
        <section className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-700">
          <Detail label="Estudiante" value={postulacion.estudiante} />
          <Detail label="Contacto" value={postulacion.email_contacto} />
          <Detail label="Estado de la postulacion" value={postulacion.estado} />
          <Detail label="Fecha de la postulacion" value={postulacion.fecha_creacion} />
        </section>

        {/* Descripción */}
        {postulacion.facultad_estudiante && (
          <section className="mt-6">
            <h2 className="text-base font-medium text-gray-800 mb-1">Detalles de la postulación</h2>
            <p className="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
              Estudiante de {postulacion.facultad_estudiante}
            </p>
          </section>
        )}

        {/* Acciones */}
        <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">
          <Link href={route('empresa.ofertas.index')}>
            <Button variant="secondary" className="w-full sm:w-auto">
              Volver
            </Button>
          </Link>

            <div className="flex gap-3 w-full sm:w-auto justify-end">

            {postulacion.canBeSelected && (
              <form onSubmit={handleSelection}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto"
                >
                  Seleccionar postulante
                </Button>
              </form>
            )}
          </div>
        </footer>
      </div>
    </EmpresaLayout>
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
