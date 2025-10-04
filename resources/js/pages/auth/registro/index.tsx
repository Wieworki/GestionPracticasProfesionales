import { Head, useForm, Link } from '@inertiajs/react';
import { useId } from "react";
import { Button } from '@/components/ui/button';
import FormLayout from '@/layouts/default-form-layout';
import React, { useState } from "react";
import RegistroEstudiante from "./registroEstudiante";
import RegistroEmpresa from "./registroEmpresa";

export default function FormularioRegistro() {
    const [visibleForm, setVisibleForm] = useState<"none" | "estudiante" | "empresa">("none");
    const panelId = useId();

    return (
        <div>
            {visibleForm === "none" && (
                <FormLayout title="Crear una cuenta" description="Seleccione el tipo de usuario">
                    <Head title="Nueva cuenta" />
                    <Button 
                        type="button" 
                        className="mt-2 w-full" 
                        aria-expanded={visibleForm === "estudiante"}
                        aria-controls={`estudiante-panel-${panelId}`}
                        onClick={() => setVisibleForm("estudiante")}
                    >
                        Estudiante
                    </Button>

                    <Button 
                        type="button" 
                        className="mt-2 w-full" 
                        aria-expanded={visibleForm === "empresa"}
                        aria-controls={`empresa-panel-${panelId}`}
                        onClick={() => setVisibleForm("empresa")}
                    >
                        Empresa
                    </Button>

                    <Link href={route('home')} >
                        <Button 
                            type="button" 
                            className="mt-2 w-full" 
                            aria-controls={`volver-panel-${panelId}`}
                        >
                            Volver
                        </Button>
                    </Link>
                </FormLayout>
            )}

            {visibleForm === "estudiante" && (
                <RegistroEstudiante 
                    onClose={() => setVisibleForm("none")} 
                />
            )}

            {visibleForm === "empresa" && (
                <RegistroEmpresa 
                    onClose={() => setVisibleForm("none")} 
                />
            )}
        </div>
    );
}
