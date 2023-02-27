import React, { Fragment, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux'
import Swal from 'sweetalert2';
import { localData } from '../../hooks/CustomHooks';
import { getData } from '../../redux/slices/dataSlice';
import EditUs from '../EditUs';
import RemoveUS from '../RemoveUS';

import './Container.css';



export const Container = () => {
  const DataList = useSelector(state => state.data);
  const { days, tickets, dias_ad, total_horas, user, role, hours } = DataList.data;

  const GenerateReport = () => {
    if (role === 'TL') {
      return (
        <a className='btn btn-primary mx-4 p-0 px-1 d-flex align-items-center' target="_blank" href="./api/index.php?generatePDF=1" >Generar Reporte</a>
      );
    }
    return <div></div>;
  }

  if (days && tickets) {
    return (
      <div className=" m-3 ">
        <div className="row">
          <div className="col ">
            <div className="mb-3" style={{ display: 'flex', justifyContent: 'space-between' }}>
              <h2>Carga de horas - [{role}]</h2>
              <div className="d-flex">
                <GenerateReport />
                <h3>Total horas cargadas: {total_horas}</h3>
              </div>
            </div>
            <div className="GenerateDays">
              {dias_ad.map((value) => {
                return (
                  <div className="" key={value}></div>
                );
              })}
              {Object.keys(days).map((value, index) => {
                return (
                  <DayContent key={value} value={value} data={days[index]} tickets={tickets} dias_ad={dias_ad} user={user.user} />
                );
              })}
            </div>
          </div>
        </div>
      </div>
    );
  }
}

const DayContent = ({ value, data, tickets, dias_ad, user }) => {
  if (!data || !tickets || !dias_ad) {
    return '';
  }

  const dispatch = useDispatch();

  const [dataUs, setDataUs] = useState({
    cargarHoras: true,
    day: data.day,
    user: user
  });

  const handleChange = (e) => {
    setDataUs({
      ...dataUs,
      [e.target.name]: e.target.value,
    });
  };

  const sendData = (e) => {
    e.preventDefault();
    localData(dataUs)
      .then((res) => {
        if (res) {
          dispatch(getData());
          Swal.fire(
            'Hora cargada!',
            '',
            'success'
          );
        }
      })
  }
  let dayStyle = {};
  if (data.name_day == 'Sabado' || data.name_day == 'Domingo') {
    dayStyle = {
      background: '#502b2b'
    };
  }

  return (
    <Fragment>
      <div className="DayContent " style={dayStyle} >
        {/* <div className="DayContent " style={dayStyle} type="button" data-bs-toggle="offcanvas" data-bs-target={"#offcanvasRight" + value} aria-controls={"offcanvasRight" + value}> */}
        <div className="primary_box">
          <div className="mb-3" style={{ textAlign: 'right', borderBottom: '#6d7ded solid 1px' }}>
            <div className='dailyComponentHead pb-1'>
              <div className="" type="button" data-bs-toggle="offcanvas" data-bs-target={"#offcanvasRight" + value} aria-controls={"offcanvasRight" + value}>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
              </div>
              <div className="">{data.name_day} {data.day}</div>
            </div>
          </div>
          <div className="horas">
            <div className="">Horas: </div>
            <div className="">{data.hours}</div>
          </div>
        </div>
        <ul className="tickets">
          {data.tickets.map((ticket, i) => {
            return (
              <li className="" key={'tickets_' + i}>
                <div className="d-flex-sb us_element">
                  <div className="">{ticket.name}</div>
                  <div className="content_remove_us">
                    {/* <EditUs id={ticket.id_hour} /> */}
                    <RemoveUS id={ticket.id_hour} />
                  </div>
                </div>

              </li>
            )
          })}
        </ul>

      </div>
      <div className="offcanvas offcanvas-end" tabIndex="-1" id={"offcanvasRight" + value} aria-labelledby={"offcanvasRightLabel" + value}>
        <div className="offcanvas-header text-dark">
          <h5 id="offcanvasRightLabel">Carga horas día {data.day}</h5>
          <button type="button" className="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div className="offcanvas-body text-dark">
          <form onSubmit={sendData}>
            <div className="input-group mb-3">
              <input list='US' className="form-control" name="ticket" placeholder="TICBC-1234" autoComplete="OFF" onChange={handleChange} />
              <datalist id="US">
                {tickets.map((ticket) => (<option value={ticket.name} key={'datalis_' + ticket.name} ></option>))}
              </datalist>
            </div>

            <div className="input-group mb-3">
              <span className="input-group-text" id="basic-addon1">HS</span>
              <input type="number" className="form-control" name="horas" placeholder="horas" onChange={handleChange} />
            </div>
            <div className="input-group mb-3">
              <button className="btn btn-primary">Cargar</button>
            </div>
          </form>
          <div className="alert alert-primary" role="alert">
            INT-5: Gestion ICBC
          </div>
          <div className="alert alert-primary" role="alert">
            INT-6: Reuniones generales
          </div>
          <div className="alert alert-primary" role="alert">
            INT-7: Capacitaciones
          </div>
          <div className="alert alert-primary" role="alert">
            INT-8: Ausentismo
          </div>
          <div className="alert alert-primary" role="alert">
            INT-34: Cumpleaños
          </div>
        </div>
      </div>
    </Fragment >
  );
}
