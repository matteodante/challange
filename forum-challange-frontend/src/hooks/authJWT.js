import useSWR from 'swr'
import axios from '@/lib/axios'
import { useEffect } from 'react'
import { useRouter } from 'next/router'

export const useAuth = ({ middleware, redirectIfAuthenticated } = {}) => {
    const router = useRouter();

    const { data: user, error, mutate } = useSWR('/api/jwt/user', () =>
        axios
            .get('/api/jwt/user')
            .then((res) => res.data)
            .catch((error) => {
                if (error.response.status !== 409) throw error;
            })
    );

    const register = async ({ setErrors, ...props }) => {
        setErrors([]);
        axios
            .post('/api/jwt/register', props)
            .then(() => mutate())
            .catch((error) => {
                if (error.response.status !== 422) throw error;
                setErrors(error.response.data.errors);
            });
    };

    const login = async ({ setErrors, setStatus, ...props }) => {
        setErrors([]);
        setStatus(null);

        axios
            .post('/api/jwt/login', props)
            .then((res) => {
                const { status, user, authorisation } = res.data;
                if (status === 'success') {
                    const { token } = authorisation;
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    mutate(user);
                }
            })
            .catch((error) => {
                if (error.response.status !== 422) throw error;
                setErrors(error.response.data.errors);
            });
    };


    const refresh = async () => {
        axios
            .post('/api/jwt/refresh')
            .then((res) => {
                const { status, user, authorisation } = res.data;
                if (status === 'success') {
                    const { token } = authorisation;
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    mutate(user);
                }
            })
            .catch(() => {
                //logout();
            });
    };

    const logout = async () => {
        //axios.defaults.headers.common['Authorization'] = '';
        //localStorage.removeItem('access_token');

        if (!error) {
            await axios.post('/api/jwt/logout').then(() => mutate())
        }
        window.location.pathname = '/login'
    };

    useEffect(() => {
        if (middleware === 'guest' && redirectIfAuthenticated && user)
            router.push(redirectIfAuthenticated);
        if (window.location.pathname === '/verify-email' && user?.email_verified_at)
            router.push(redirectIfAuthenticated);
        //if (middleware === 'auth' && error) logout();
    }, [user, error]);

    useEffect(() => {
        const access_token = localStorage.getItem('access_token');
        if (access_token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
            //refresh();
        }
    }, []);

    return {
        user,
        register,
        login,
        logout,
        refresh,
    };
};
