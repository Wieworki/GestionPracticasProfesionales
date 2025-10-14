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
    cuit: string,
    descripcion: string,
    sitio_web: string,
    email: string;
    telefono: string;
    password: string;
    password_confirmation: string;
};

interface RegistroEmpresaProps {
    onClose: () => void;
}

export default function RegistroEmpresa({ onClose }: RegistroEmpresaProps) {
    
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        nombre: '',
        cuit: '',
        descripcion: '',
        sitio_web: '',
        email: '',
        telefono: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register.empresa'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <FormLayout title="Nueva empresa" description="Registro de una nueva empresa en el sistema">
            <Head title="Nueva empresa" />
            <form className="flex flex-col gap-6" onSubmit={submit}>
                <div className="grid gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="name">Nombre</Label>
                        <Input
                            id="nombre"
                            type="text"
                            required
                            autoFocus
                            autoComplete="nombre"
                            value={data.nombre}
                            onChange={(e) => setData('nombre', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="name">Cuit</Label>
                        <Input
                            id="cuit"
                            type="text"
                            required
                            autoFocus
                            autoComplete="cuit"
                            value={data.cuit}
                            onChange={(e) => setData('cuit', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.cuit} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="name">Descripcion</Label>
                        <Input
                            id="descripcion"
                            type="text"
                            required
                            autoFocus
                            autoComplete="descripcion"
                            value={data.descripcion}
                            onChange={(e) => setData('descripcion', e.target.value)}
                            disabled={processing}
                            placeholder="Detalles de las actividades de la empresa/organizacion"
                        />
                        <InputError message={errors.descripcion} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="name">Sitio web</Label>
                        <Input
                            id="sitio_web"
                            type="text"
                            required
                            autoFocus
                            autoComplete="sitio_web"
                            value={data.sitio_web}
                            onChange={(e) => setData('sitio_web', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.sitio_web} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="telefono">Telefono</Label>
                        <Input
                            id="telefono"
                            type="text"
                            required
                            autoFocus
                            autoComplete="telefono"
                            value={data.telefono}
                            onChange={(e) => setData('telefono', e.target.value)}
                            disabled={processing}
                            placeholder=""
                        />
                        <InputError message={errors.telefono} className="mt-2" />
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
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Volver
                    </Button>

                    <div className='' data-aux="Total de errores en el form">
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
