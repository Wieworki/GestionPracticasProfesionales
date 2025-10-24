import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import { Button } from '@/components/ui/button';

interface Props {
  empresa: {
    id: number;
    nombre: string;
    descripcion: string;
    email_contacto: string;
    sitio_web: string;
    convenio: string;
    cuit: string;
    telefono: string;
    permitir_habilitar_convenio: boolean;
  };
  administrativo: {
    nombre: string;
  };
}

export default function ShowEmpresa({ empresa, administrativo }: Props) {
  const { patch, processing } = useForm({ id: empresa.id });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    patch(route('administrativo.empresas.convenio'));
  };

  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        {/* Título */}
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Detalle de la Empresa
          </h1>
        </header>

        {/* Información principal */}
        <section className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-700">
          <Detail label="Nombre" value={empresa.nombre} />
          <Detail label="CUIT" value={empresa.cuit} />
          <Detail label="Convenio" value={empresa.convenio || '—'} />
          <Detail label="Teléfono" value={empresa.telefono || '—'} />
          <Detail label="Email de contacto" value={empresa.email_contacto || '—'} />
          <Detail
            label="Sitio web"
            value={
              empresa.sitio_web ? (
                <a
                  href={empresa.sitio_web}
                  className="text-blue-600 hover:underline break-all"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {empresa.sitio_web}
                </a>
              ) : (
                '—'
              )
            }
          />
        </section>

        {/* Descripción */}
        {empresa.descripcion && (
          <section className="mt-6">
            <h2 className="text-base font-medium text-gray-800 mb-1">Descripción</h2>
            <p className="text-gray-700 leading-relaxed text-sm whitespace-pre-line">
              {empresa.descripcion}
            </p>
          </section>
        )}

        {/* Acciones */}
        <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">
          <Link href="/administrativo/empresas">
            <Button variant="secondary" className="w-full sm:w-auto">
              Volver
            </Button>
          </Link>

          <div className="flex gap-3 w-full sm:w-auto justify-end">
            {empresa.permitir_habilitar_convenio && (
              <form onSubmit={handleSubmit}>
                <Button
                  type="submit"
                  disabled={processing}
                  className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto"
                >
                  Confirmar convenio
                </Button>
              </form>
            )}

            <Link href={`/administrativo/ofertas?empresa_id=${empresa.id}`}>
              <Button variant="default" className="w-full sm:w-auto">
                Ver ofertas
              </Button>
            </Link>
          </div>
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
