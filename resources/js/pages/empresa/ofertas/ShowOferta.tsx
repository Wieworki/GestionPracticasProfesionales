import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import EmpresaLayout from '@/layouts/dashboard/EmpresaLayout';
import { Button } from '@/components/ui/button';

interface Props {
  empresa: {
    nombre: string;
  };
  oferta: {
    id: number;
    titulo: string;
    descripcion: string;
    fecha_cierre: string;
    modalidad: string;
    estado: string;
    isEditable: boolean;
    isDeleted: boolean;
    canBeDeleted: boolean;
  };
}

export default function ShowOferta({ empresa, oferta }: Props) {
  const { patch, processing } = useForm({});

  const handleDelete = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que deseas eliminar esta oferta?')) {
      patch(route('empresa.ofertas.eliminar', oferta.id));
    }
  };

  return (
    <EmpresaLayout nombre={empresa.nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        {/* Título */}
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Detalle de la oferta
          </h1>
        </header>

        {/* Información principal */}
        <section className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-700">
          <Detail label="Título" value={oferta.titulo} />
          <Detail label="Modalidad" value={oferta.modalidad} />
          <Detail label="Fecha de cierre" value={oferta.fecha_cierre} />
          <Detail label="Estado" value={oferta.estado} />
        </section>

        {/* Descripción */}
        {oferta.descripcion && (
          <section className="mt-6">
            <h2 className="text-base font-medium text-gray-800 mb-1">Descripción</h2>
            <p className="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
              {oferta.descripcion}
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
            {oferta.isEditable && (
                <Link href={route('empresa.ofertas.edit', oferta.id)}>
                  <Button className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto">
                    Modificar
                  </Button>
                </Link>
            )}

            {!oferta.isDeleted && (
              <Link href={route('empresa.ofertas.postulantes', { ofertaId: oferta.id })}>
                <Button variant="default" className="w-full sm:w-auto">
                  Ver postulantes
                </Button>
              </Link>
            )}

            {oferta.canBeDeleted && (
              <form onSubmit={handleDelete}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-red-600 hover:bg-red-700 text-white w-full sm:w-auto"
                >
                  Eliminar
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
