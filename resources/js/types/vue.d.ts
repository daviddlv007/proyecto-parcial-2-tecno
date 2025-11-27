declare module '*.vue' {
    import type { DefineComponent } from 'vue'
    const component: DefineComponent<{}, {}, any>
    export default component
}

// Tipos para Inertia.js
declare module '@inertiajs/vue3' {
    import { DefineComponent } from 'vue'
    import { AxiosInstance } from 'axios'
    import { route as ziggyRoute } from 'ziggy-js'
    
    export const Head: DefineComponent
    export const Link: DefineComponent
    
    export function createInertiaApp(options: any): Promise<void>
    export function useForm<T = any>(data?: T): any
    export function usePage<T = any>(): any
    export function router(): any
}

declare global {
    const route: typeof ziggyRoute
}
