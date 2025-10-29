import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import { Button } from '@/components/ui/button';

interface Props {
  nombre: string;
  oferta: {
    id: number;
    titulo: string;
    descripcion: string;
    fecha_cierre: string;
    modalidad: string;
    estado: string;
    canPostularse: boolean;
  };
}

export default function ShowOferta({ nombre, oferta }: Props) {
  const { patch, processing } = useForm({});

  const handlePostulacion = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que desea postularse esta oferta?')) {
      patch(route('estudiante.oferta.postular', oferta.id));
    }
  };

  return (
    <EstudianteLayout nombre={nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Detalle de la oferta
          </h1>
        </header>

        <section className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-700">
          <Detail label="Título" value={oferta.titulo} />
          <Detail label="Modalidad" value={oferta.modalidad} />
          <Detail label="Fecha de cierre" value={oferta.fecha_cierre} />
          <Detail label="Estado" value={oferta.estado} />
        </section>

        {oferta.descripcion && (
          <section className="mt-6">
            <h2 className="text-base font-medium text-gray-800 mb-1">Descripción</h2>
            <p className="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
              {oferta.descripcion}
            </p>
          </section>
        )}

        <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">
          <Link href={route('estudiante.ofertas.index')}>
            <Button variant="secondary" className="w-full sm:w-auto">
              Volver
            </Button>
          </Link>

            <div className="flex gap-3 w-full sm:w-auto justify-end">
            {oferta.canPostularse && (
              <form onSubmit={handlePostulacion}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto"
                >
                  Postularse
                </Button>
              </form>
            )}
          </div>
        </footer>
      </div>
    </EstudianteLayout>
  );
}

function Detail({ label, value }: { label: string; value: React.ReactNode }) {
  return (
    <div className="flex flex-col">
      <span className="text-xs text-gray-500">{label}</span>
      <span className="font-medium text-gray-900 text-sm truncate">{value}</span>
    </div>
  );
}
