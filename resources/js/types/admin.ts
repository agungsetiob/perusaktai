export interface ComplaintRow {
    id: number
    tracking_code: string
    name: string | null
    is_anonymous: boolean
    status: string
    submitted_at: string

    category: {
        id: number
        name: string
    }
}