import React from 'react'
import { useDispatch } from 'react-redux';
import { localData } from '../hooks/CustomHooks'
import { getData } from '../redux/slices/dataSlice';
import Swal from 'sweetalert2';


export default function RemoveUS({ id }) {
    const dispatch = useDispatch();

    const removeHs = () => {
        Swal.fire({
            title: 'Necesito confirmación',
            text: "Seguro que deseas eliminar la hora cargada?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            CancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                localData({ removeHsUser: true, remove_hs_id: id }).then((res) => {
                    if (res) {
                        dispatch(getData());
                        Swal.fire(
                            'Hora Eliminada',
                            '',
                            'success'
                        );
                    }
                })
            }
        })

    }

    return (
        <div style={{ cursor: 'pointer' }} onClick={removeHs} >
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" className="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
            </svg>
        </div>
    )
}
