import React from 'react';
import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface Props {
    ruta: string;
}

export default function BotonVolver({ ruta }: Props) {
    return (
        <Button
        variant="secondary"
        className="w-full sm:w-auto"
        onClick={() => {
            // Si hay historial, volvemos a la página anterior
            if (window.history.length > 1) {
                // Forzamos refresh de la página anterior
                router.reload({ only: [] }); // esto refresca si    estás en la misma vista
                window.history.back();
            } else {
            // Si no hay historial (ej. entró directo)
            router.visit(route(ruta), { replace: true });
            }
        }}
        >
        Volver
        </Button>
    );
}