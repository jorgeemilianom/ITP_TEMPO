import { useEffect, useState } from 'react'
import { Provider, useDispatch } from 'react-redux'
import { store } from './redux/ReduxHook'
import { getData } from './redux/slices/dataSlice';
import { Container } from './components/Container/Container'
import Swal from 'sweetalert2';
import { localData } from './hooks/CustomHooks';

function App() {
  const [user, setUser] = useState(false);
  const dispatch = useDispatch();

  if (!user) {
    Swal.fire({
      title: 'Ingresa usuario',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off'
      },
      showCancelButton: true,
      confirmButtonText: 'Cargar horas',
      showLoaderOnConfirm: true,
      preConfirm: (user) => {
        return localData({ user })
          .then(response => {
            if (!response.status) {
              throw new Error(response.message)
            }

            setUser(true);
            dispatch(getData());
          })
          .catch(error => {
            Swal.showValidationMessage(
              `${error}`
            )
          })
      }
    })
  }

  if (user) {
    return <Container />
  }

}

export default App
