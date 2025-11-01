import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import { Button } from '@/components/ui/button';
import BotonVolver from '@/components/basicos/botonVolver';

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
  postulacion: {
    id: number;
    existe: boolean;
    fecha: string;
    seleccionada: boolean;
    confirmada: boolean;
    canAnular: boolean;
    mensajePostulacion: string;
  };
}

export default function ShowOferta({ nombre, oferta, postulacion }: Props) {
  const { patch, processing } = useForm({});

  const handlePostulacion = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que desea postularse esta oferta?')) {
      patch(route('estudiante.oferta.postular', oferta.id));
    }
  };
  
  const handleConfirmacion = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que deseas confirmar?')) {
      patch(route('estudiante.postulacion.confirmar', { postulacionId: postulacion.id }));
    }
  };

  const handleAnulacion = (e: React.FormEvent) => {
    e.preventDefault();
    if (confirm('¿Estás seguro de que desea anular la postulacion?')) {
      patch(route('estudiante.postulacion.anular', { postulacionId: postulacion.id }));
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

        {postulacion.existe && (
          <section className="mt-6">
            <h2 className="text-base font-medium text-gray-800 mb-1">Postulacion hecha</h2>
            <p className="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
              {postulacion.mensajePostulacion}
            </p>

            {postulacion.seleccionada && (
              <div className="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 className="text-base font-semibold text-blue-700 mb-2">
                  ¡Fuiste seleccionado!
                </h3>
                <p className="text-gray-700 text-sm leading-relaxed">
                  ¡Felicitaciones! Has sido seleccionado por la empresa para realizar la práctica profesional correspondiente a esta oferta.
                  <br />
                  Si deseas confirmar tu participación, presioná el botón de <strong>“Confirmar postulación”</strong>. 
                </p>
              </div>
            )}

            {postulacion.confirmada && (
              <div className="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 className="text-base font-semibold text-blue-700 mb-2">
                  Postulacion confirmada
                </h3>
                <p className="text-gray-700 text-sm leading-relaxed">
                  Confirmaste que vas a realizar la práctica profesional. Se te contactará para más detalles.
                </p>
              </div>
            )}
          </section>
        )}

        <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">

            <BotonVolver ruta='estudiante.ofertas.index'>
              Volver
            </BotonVolver>

            <div className="flex gap-3 w-full sm:w-auto justify-end">

            {postulacion.canAnular && (
              <form onSubmit={handleAnulacion}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-red-600 hover:bg-red-700 text-white w-full sm:w-auto"
                >
                  Anular postulacion
                </Button>
              </form>
            )}

            {postulacion.seleccionada && (
              <form onSubmit={handleConfirmacion}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto"
                >
                  Confirmar postulacion
                </Button>
              </form>
            )}

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
