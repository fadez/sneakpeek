export default function formatDate(value: string | Date | number | null | undefined): string {
    if (!value) return '';

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return '';

    return date.toLocaleString('en', {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
}
