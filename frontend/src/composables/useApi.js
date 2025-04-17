import { ref } from 'vue'

export default function useApi(resource) {
    const data = ref(null)
    const error = ref(null)
    const loading = ref(false)

    const baseUrl = 'http://localhost:8000/' + resource

    const fetchAll = async () => {
        loading.value = true
        try {
            const res = await fetch(baseUrl)
            if (!res.ok) throw new Error('Failed to fetch data')
            data.value = await res.json()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const fetchOne = async (id) => {
        loading.value = true
        try {
            const res = await fetch(`${baseUrl}/${id}`)
            if (!res.ok) throw new Error('Failed to fetch item')
            data.value = await res.json()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }


    const create = async (payload) => {
        loading.value = true
        error.value = null
        data.value = null

        try {
            const res = await fetch(baseUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: payload,
            })

            const responseData = await res.json()
            data.value = responseData

            if (!res.ok) {
                if (responseData.errors) {
                    const allErrors = Object.values(responseData.errors)
                        .flat()
                        .join('\n')

                    error.value = allErrors
                    alert(allErrors)
                } else {
                    const message = responseData.message || 'Erro inesperado'
                    error.value = message
                    alert(message)
                }
            }
        } catch (err) {
            error.value = 'Erro ao conectar com o servidor.'
            alert(error.value)
        } finally {
            loading.value = false
        }
    }

    const update = async (id, payload) => {
        loading.value = true
        error.value = null
        try {
            const res = await fetch(`${baseUrl}/${id}`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                credentials: 'include',
                body: payload,
            })

            if (!res.ok) {
                const err = await res.json()
                throw new Error(err.message || 'Failed to update item')
            }

            return await res.json()
        } catch (err) {
            error.value = err
            return null
        } finally {
            loading.value = false
        }
    }

    const destroy = async (id) => {
        loading.value = true
        try {
            const res = await fetch(`${baseUrl}/${id}`, { method: 'DELETE' })
            if (!res.ok) throw new Error('Failed to delete')
            data.value = await res.json()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    return {
        data,
        error,
        loading,
        fetchAll,
        fetchOne,
        create,
        update,
        destroy,
    }
}
