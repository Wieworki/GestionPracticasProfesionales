import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import FormLayout from '@/layouts/default-form-layout';

type RegisterForm = {
    nombre: string;
    apellido: string;
    email: string;
    dni: string;
    password: string;
    password_confirmation: string;
};

type Errors = {
  [field: string]: string;
}

interface RegistroEstudianteProps {
    onClose: () => void;
}

export default function RegistroEstudiante({ onClose }: RegistroEstudianteProps) {
    
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        nombre: '',
        apellido: '',
        email: '',
        dni: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register.estudiante'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <FormLayout title="Nuevo estudiante" description="Registro de un nuevo estudiante en el sistema">
            <Head title="Nuevo estudiante" />
            <form className="flex flex-col gap-6" onSubmit={submit}>
                <div className="grid gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="nombre">Nombre</Label>
                        <Input
                            id="nombre"
                            type="text"
                            required
                            autoFocus
                            autoComplete="nombre"
                            value={data.nombre}
                            onChange={(e) => setData('nombre', e.target.value)}
                            disabled={processing}
                            placeholder="Nombre"
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="apellido">Apellido</Label>
                        <Input
                            id="apellido"
                            type="text"
                            required
                            autoFocus
                            autoComplete="apellido"
                            value={data.apellido}
                            onChange={(e) => setData('apellido', e.target.value)}
                            disabled={processing}
                            placeholder="Apellido"
                        />
                        <InputError message={errors.apellido} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            autoComplete="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            disabled={processing}
                            placeholder="email@example.com"
                        />
                        <InputError message={errors.email} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="dni">DNI</Label>
                        <Input
                            id="dni"
                            type="text"
                            required
                            autoFocus
                            autoComplete="dni"
                            value={data.dni}
                            onChange={(e) => setData('dni', e.target.value)}
                            disabled={processing}
                            placeholder="20-12345678-9"
                        />
                        <InputError message={errors.dni} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password">Contraseña</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            autoComplete="new-password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.password} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            autoComplete=""
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.password_confirmation} />
                    </div>

                    <Button type="submit" className="mt-2 w-full" disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Continuar
                    </Button>
                    <Button type="button" className="mt-2 w-full" onClick={onClose}>
                        Volver
                    </Button>

                    <div className='hidden' data-aux="Total de errores en el form">
                        {Object.values(errors).length > 0 && (
                            <div className="rounded-md bg-red-50 p-4 text-sm text-red-600">
                                <ul className="list-disc list-inside">
                                    {Object.values(errors).map((error, index) => (
                                        <li key={index}>{error}</li>
                                    ))}
                                </ul>
                            </div>
                        )}
                    </div>

                </div>
            </form>
        </FormLayout>
    );
}
