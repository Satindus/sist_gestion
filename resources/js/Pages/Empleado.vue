<template>
    <AppLayout title="Empleado">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <h1>Gestión de Permiso-Rol-Módulo</h1>
                        <form @submit.prevent="guardar">
                            <div>
                                <label>Rol ID:</label>
                                <input type="number" v-model="form.rol_id">
                            </div>
                            <div>
                                <label>Módulo ID:</label>
                                <input type="number" v-model="form.modulo_id">
                            </div>
                            <div>
                                <label>Permiso ID:</label>
                                <input type="number" v-model="form.permiso_id">
                            </div>
                            <button type="submit">{{ isEditing ? 'Actualizar' : 'Guardar' }}</button>
                        </form>

                        <table>
                            <thead>
                                <tr>
                                    <th>Rol ID</th>
                                    <th>Módulo ID</th>
                                    <th>Permiso ID</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in lista"
                                    :key="item.rol_id + '-' + item.modulo_id + '-' + item.permiso_id">
                                    <td>{{ item.rol_id }}</td>
                                    <td>{{ item.modulo_id }}</td>
                                    <td>{{ item.permiso_id }}</td>
                                    <td>
                                        <button @click="editar(item)">Editar</button>
                                        <button @click="eliminar(item)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<script>
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    data() {
        return {
            lista: [],
            form: {
                rol_id: '',
                modulo_id: '',
                permiso_id: ''
            },
            isEditing: false,
            originalKeys: {} // Almacena las claves originales cuando se edita un registro
        }
    },
    methods: {
        fetchData() {
            axios.get('/api/permiso-rol-modulo')
                .then(response => {
                    this.lista = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        guardar() {
            if (this.isEditing) {
                // Actualizar registro
                axios.put(`/api/permiso-rol-modulo/${this.originalKeys.rol_id}/${this.originalKeys.modulo_id}/${this.originalKeys.permiso_id}`, this.form)
                    .then(response => {
                        this.fetchData();
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                // Crear nuevo registro
                axios.post('/api/permiso-rol-modulo', this.form)
                    .then(response => {
                        this.fetchData();
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        editar(item) {
            // Copiar los datos para editar y guardar las claves originales
            this.form = { ...item };
            this.originalKeys = {
                rol_id: item.rol_id,
                modulo_id: item.modulo_id,
                permiso_id: item.permiso_id
            };
            this.isEditing = true;
        },
        eliminar(item) {
            if (confirm('¿Está seguro de eliminar este registro?')) {
                axios.delete(`/api/permiso-rol-modulo/${item.rol_id}/${item.modulo_id}/${item.permiso_id}`)
                    .then(response => {
                        this.fetchData();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        resetForm() {
            this.form = {
                rol_id: '',
                modulo_id: '',
                permiso_id: ''
            };
            this.isEditing = false;
            this.originalKeys = {};
        }
    },
    mounted() {
        this.fetchData();
    }
}
</script>

<style scoped>
/* Estilos básicos: adáptalos a tus necesidades */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
}

form div {
    margin-bottom: 10px;
}
</style>