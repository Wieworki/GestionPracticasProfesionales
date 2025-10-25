import React, { useState } from 'react';
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";
import FormNuevaOferta from "@/pages/Oferta/Nueva";

interface NuevaOfertaProps {
    empresa: {
        nombre: string;
    };
}

export default function NuevaOferta({ empresa }: NuevaOfertaProps) {

  return (
    <EmpresaLayout nombre={empresa.nombre}>
        <FormNuevaOferta></FormNuevaOferta>
    </EmpresaLayout>
  );
};
