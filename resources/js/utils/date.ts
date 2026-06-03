export function formatDateTime(
    value: string | null | undefined
): string {

    if (!value) {
        return '-'
    }

    const date = new Date(value)

    const tanggal = new Intl.DateTimeFormat(
        'id-ID',
        {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }
    ).format(date)

    const jam = new Intl.DateTimeFormat(
        'id-ID',
        {
            hour: '2-digit',
            minute: '2-digit',
        }
    ).format(date)

    return `${tanggal}, ${jam}`
}