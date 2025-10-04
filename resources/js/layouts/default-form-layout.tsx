import FormSimpleLayout from '@/layouts/app/form-simple-layout';

export default function DefaultFormLayout({ children, title, description, ...props }: { children: React.ReactNode; title: string; description: string }) {
    return (
        <FormSimpleLayout title={title} description={description} {...props}>
            {children}
        </FormSimpleLayout>
    );
}
