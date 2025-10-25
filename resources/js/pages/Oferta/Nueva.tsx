import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import { Button } from "@/components/ui/button";

interface FormData {
  titulo: string;
  descripcion: string;
  fecha_cierre: string;
  modalidad: string;
}

export default function NuevaOferta() {
  const { data, setData, post, processing, errors } = useForm<FormData>({
    titulo: '',
    descripcion: '',
    fecha_cierre: '',
    modalidad: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('empresa.ofertas.store'));
  };

  return (
      <div className="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow">
        <h1 className="text-2xl font-semibold mb-6 text-gray-800">
          Crear nueva oferta
        </h1>

        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-medium text-gray-700">Título</label>
            <input
              type="text"
              value={data.titulo}
              onChange={(e) => setData('titulo', e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
            {errors.titulo && (
              <p className="text-red-500 text-sm mt-1">{errors.titulo}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea
              value={data.descripcion}
              onChange={(e) => setData('descripcion', e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              rows={4}
            />
            {errors.descripcion && (
              <p className="text-red-500 text-sm mt-1">{errors.descripcion}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Fecha de cierre</label>
            <input
              type="date"
              value={data.fecha_cierre}
              onChange={(e) => setData('fecha_cierre', e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
            {errors.fecha_cierre && (
              <p className="text-red-500 text-sm mt-1">{errors.fecha_cierre}</p>
            )}
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

          <div className="flex justify-end gap-3 pt-6 border-t">
            <Link href="/empresa/dashboard">
              <Button variant="secondary">Volver</Button>
            </Link>
            <Button
              type="submit"
              disabled={processing}
              className="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
            >
              {processing ? 'Guardando...' : 'Crear oferta'}
            </Button>
          </div>
        </form>
      </div>
  );
};
