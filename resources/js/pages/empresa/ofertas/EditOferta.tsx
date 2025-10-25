import React from "react";
import { useForm, Head, Link } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";

interface Props {
  empresa: { nombre: string };
  oferta: {
    id: number;
    titulo: string;
    descripcion: string;
    fecha_cierre: string;
    modalidad: string;
  };
}

export default function EditOferta({ empresa, oferta }: Props) {
  const { data, setData, patch, processing, errors } = useForm({
    titulo: oferta.titulo || "",
    modalidad: oferta.modalidad || "",
    fecha_cierre: oferta.fecha_cierre || "",
    descripcion: oferta.descripcion || "",
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    patch(route("empresa.ofertas.update", oferta.id));
  };

  return (
    <EmpresaLayout nombre={empresa.nombre}>
      <Head title="Editar oferta" />
      <div className="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h1 className="text-2xl font-semibold text-gray-800 mb-6">
          Editar oferta
        </h1>

        <form onSubmit={submit} className="grid grid-cols-2 gap-6">
          <div>
            <Label htmlFor="titulo">Título</Label>
            <Input
              id="titulo"
              value={data.titulo}
              onChange={(e) => setData("titulo", e.target.value)}
            />
            <InputError message={errors.titulo} />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Modalidad</label>
            <select
              value={data.modalidad}
              onChange={(e) => setData('modalidad', e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"
            >
              <option value="">Seleccionar...</option>
              <option value="Presencial">Presencial</option>
              <option value="Remoto">Remoto</option>
              <option value="Híbrido">Híbrido</option>
            </select>
            {errors.modalidad && (
              <p className="text-red-500 text-sm mt-1">{errors.modalidad}</p>
            )}
          </div>

          <div>
            <Label htmlFor="fecha_cierre">Fecha de cierre</Label>
            <Input
              type="date"
              id="fecha_cierre"
              value={data.fecha_cierre}
              onChange={(e) => setData("fecha_cierre", e.target.value)}
            />
            <InputError message={errors.fecha_cierre} />
          </div>

          <div className="col-span-2">
            <Label htmlFor="descripcion">Descripción</Label>
            <textarea
              id="descripcion"
              className="w-full border rounded-lg p-2 text-sm"
              rows={5}
              value={data.descripcion}
              onChange={(e) => setData("descripcion", e.target.value)}
            />
            <InputError message={errors.descripcion} />
          </div>

          <div className="col-span-2 flex justify-between mt-4">
            <Link href={route("empresa.ofertas.show", oferta.id)}>
              <Button variant="secondary">Volver</Button>
            </Link>
            <Button type="submit" disabled={processing}>
              Guardar cambios
            </Button>
          </div>
        </form>
      </div>
    </EmpresaLayout>
  );
}
