import { ref } from 'vue'

const user = ref(null)

export function useAuth() {
    async function checkAuth() {
        try {
            // await fetch('http://localhost:8000/sanctum/csrf-cookie', {
            //     credentials: 'include'
            // })

            const res = await fetch('http://localhost:8000/api/me', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            })

            if (res.ok) {
                user.value = await res.json()
            } else {
                user.value = null
            }
        } catch {
            user.value = null
        }
    }

    return {
        user,
        checkAuth,
    }
}
