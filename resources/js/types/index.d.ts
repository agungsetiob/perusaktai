export interface User {
    id: number
    name: string
    email: string
    role: 'admin' | 'supervisor' | 'super_admin'
    email_verified_at?: string | null
}

export interface SharedData {
    auth: {
        user: User | null
    }
}

notifications: Array<{
    id: string
    data: {
        title: string
        message: string
        complaint_id: number
        tracking_code: string
    }
    created_at: string
}>
