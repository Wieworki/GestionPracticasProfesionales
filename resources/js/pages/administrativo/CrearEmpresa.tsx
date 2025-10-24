import React from "react";
import { Head, useForm, router } from "@inertiajs/react";
import { LoaderCircle } from "lucide-react";
import AdministrativoLayout from "@/layouts/dashboard/AdministrativoLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

interface Props {
  administrativo: {
    nombre: string;
  };
}

export default function CrearEmpresa({ administrativo }: Props) {
  const { data, setData, post, processing, errors, reset } = useForm({
    nombre: "",
    email: "",
    password: "",
    telefono: "",
    cuit: "",
    descripcion: "",
    sitio_web: "",
    password_confirmation: "",
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route("administrativo.empresas.store"), {
      onSuccess: () => reset("password", "password_confirmation"),
    });
  };

  return (
    <AdministrativoLayout nombre={administrativo.nombre}>
      <Head title="Registrar Empresa" />

      <div className="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mt-6">
        <h1 className="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">
          Registrar nueva empresa
        </h1>

        <form onSubmit={submit} className="space-y-8">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
            <Field label="Nombre" error={errors.nombre}>
              <Input
                id="nombre"
                value={data.nombre}
                onChange={(e) => setData("nombre", e.target.value)}
                disabled={processing}
                placeholder="Ej: TechCorp S.A."
              />
            </Field>

            <Field label="Email" error={errors.email}>
              <Input
                id="email"
                type="email"
                value={data.email}
                onChange={(e) => setData("email", e.target.value)}
                disabled={processing}
                placeholder="contacto@empresa.com"
              />
            </Field>

            <Field label="Teléfono" error={errors.telefono}>
              <Input
                id="telefono"
                value={data.telefono}
                onChange={(e) => setData("telefono", e.target.value)}
                disabled={processing}
                placeholder="3415551122"
              />
            </Field>

            <Field label="CUIT" error={errors.cuit}>
              <Input
                id="cuit"
                value={data.cuit}
                onChange={(e) => setData("cuit", e.target.value)}
                disabled={processing}
                placeholder="30-12345678-9"
              />
            </Field>

            <Field label="Contraseña" error={errors.password}>
              <Input
                id="password"
                type="password"
                value={data.password}
                onChange={(e) => setData("password", e.target.value)}
                disabled={processing}
                placeholder="Contraseña inicial"
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

            <Field label="Sitio Web" error={errors.sitio_web}>
              <Input
                id="sitio_web"
                value={data.sitio_web}
                onChange={(e) => setData("sitio_web", e.target.value)}
                disabled={processing}
                placeholder="https://www.techcorp.com"
              />
            </Field>
          </div>

          <div>
            <Field label="Descripción" error={errors.descripcion}>
              <Textarea
                id="descripcion"
                value={data.descripcion}
                onChange={(e) => setData("descripcion", e.target.value)}
                disabled={processing}
                rows={5}
                placeholder="Describe brevemente a la empresa..."
              />
            </Field>
          </div>

          <div className="flex justify-between pt-6 border-t mt-8">
            <Button
              type="button"
              variant="secondary"
              onClick={() =>
                router.visit(route("administrativo.empresas.index"))
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
