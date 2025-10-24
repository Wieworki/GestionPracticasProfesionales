import React from "react";
import { Head, useForm, router } from "@inertiajs/react";
import { LoaderCircle } from "lucide-react";
import AdministrativoLayout from "@/layouts/dashboard/AdministrativoLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface Props {
  administrativo: {
    nombre: string;
  };
}

export default function CrearEstudiante({ administrativo }: Props) {
  const { data, setData, post, processing, errors, reset } = useForm({
    nombre: "",
    apellido: "",
    email: "",
    telefono: "",
    dni: "",
    password: "",
    password_confirmation: "",
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route("administrativo.estudiantes.store"), {
      onSuccess: () => reset("password", "password_confirmation"),
    });
  };

  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <Head title="Nuevo Estudiante" />

      <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mt-6">
        <h1 className="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">
          Registrar nuevo estudiante
        </h1>

        <form onSubmit={submit} className="space-y-6">
          {/* --- GRID EN 2 COLUMNAS --- */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
            <Field label="Nombre" error={errors.nombre}>
              <Input
                value={data.nombre}
                onChange={(e) => setData("nombre", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field label="Apellido" error={errors.apellido}>
              <Input
                value={data.apellido}
                onChange={(e) => setData("apellido", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field label="Email" error={errors.email}>
              <Input
                type="email"
                value={data.email}
                onChange={(e) => setData("email", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field label="Teléfono" error={errors.telefono}>
              <Input
                value={data.telefono}
                onChange={(e) => setData("telefono", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field label="Contraseña" error={errors.password}>
              <Input
                type="password"
                value={data.password}
                onChange={(e) => setData("password", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field label="DNI" error={errors.dni}>
              <Input
                value={data.dni}
                onChange={(e) => setData("dni", e.target.value)}
                disabled={processing}
              />
            </Field>

            <Field
              label="Confirmar contraseña"
              error={errors.password_confirmation}
            >
              <Input
                type="password"
                value={data.password_confirmation}
                onChange={(e) =>
                  setData("password_confirmation", e.target.value)
                }
                disabled={processing}
              />
            </Field>
          </div>

          {/* --- BOTONES --- */}
          <div className="flex justify-between pt-6 border-t mt-6">
            <Button
              type="button"
              variant="secondary"
              onClick={() =>
                router.visit(route("administrativo.estudiantes.index"))
              }
              disabled={processing}
              className="w-full md:w-auto"
            >
              Volver
            </Button>

            <Button
              type="submit"
              disabled={processing}
              className="bg-blue-600 hover:bg-blue-700 text-white w-full md:w-auto"
            >
              {processing && (
                <LoaderCircle className="w-4 h-4 animate-spin mr-2" />
              )}
              Confirmar
            </Button>
          </div>
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
