import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Props {
  estudiante: {
    id: number;
    nombre: string;
    apellido: string;
    dni: string;
    telefono: string | null;
    email: string;
    habilitado: boolean;
  };
  administrativo: {
    nombre: string;
  };
}

export default function EditEstudiante({ estudiante, administrativo }: Props) {
  const { data, setData, patch, processing, errors } = useForm({
    nombre: estudiante.nombre,
    apellido: estudiante.apellido,
    dni: estudiante.dni,
    telefono: estudiante.telefono || '',
    email: estudiante.email,
    habilitado: estudiante.habilitado ? '1' : '0',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    patch(route('administrativo.estudiantes.update', estudiante.id));
  };

  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <div className="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        <header className="mb-6 border-b pb-3">
          <h1 className="text-xl sm:text-2xl font-semibold text-gray-800">Editar Estudiante</h1>
        </header>

        <form onSubmit={handleSubmit} className="space-y-5">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <Field label="Nombre" error={errors.nombre}>
              <Input value={data.nombre} onChange={(e) => setData('nombre', e.target.value)} />
            </Field>

            <Field label="Apellido" error={errors.apellido}>
              <Input value={data.apellido} onChange={(e) => setData('apellido', e.target.value)} />
            </Field>

            <Field label="DNI" error={errors.dni}>
              <Input value={data.dni} onChange={(e) => setData('dni', e.target.value)} />
            </Field>

            <Field label="Teléfono" error={errors.telefono}>
              <Input value={data.telefono} onChange={(e) => setData('telefono', e.target.value)} />
            </Field>

            <Field label="Email" error={errors.email}>
              <Input value={data.email} onChange={(e) => setData('email', e.target.value)} />
            </Field>

            <Field label="Estado" error={errors.habilitado}>
              <Select
                value={data.habilitado}
                onValueChange={(v) => setData('habilitado', v)}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Seleccionar estado" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="1">Habilitado</SelectItem>
                  <SelectItem value="0">Deshabilitado</SelectItem>
                </SelectContent>
              </Select>
            </Field>
          </div>

          <footer className="flex flex-col sm:flex-row justify-between items-center gap-3 mt-10">
            <Link href={route('administrativo.estudiantes.show', estudiante.id)}>
              <Button variant="secondary" className="w-full sm:w-auto">
                Volver
              </Button>
            </Link>

            <Button
              type="submit"
              disabled={processing}
              className="bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto"
            >
              Confirmar
            </Button>
          </footer>
        </form>
      </div>
    </AdministrativoLayout>
  );
}

/* --- Componente auxiliar --- */
function Field({
  label,
  error,
  children,
}: {
  label: string;
  error?: string;
  children: React.ReactNode;
}) {
  return (
    <div className="flex flex-col">
      <Label className="mb-1 text-sm text-gray-700">{label}</Label>
      {children}
      {error && <span className="text-xs text-red-600 mt-1">{error}</span>}
    </div>
  );
}
