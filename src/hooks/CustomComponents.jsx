import React, { useState, useEffect, createContext, useRef } from 'react';
import ReactDOM from 'react-dom/client';
import '../assets/CustomComponents.css';

export function Test() {

    return (
        <h1>tests</h1>
    )
}

export function HeadPage({ title, elements = [] }) {

    let btnPrimary = {
        background: '#fff',
        color: '#000',
        padding: '7px 16px',
        border: '#dddddd solid 1px',
        borderRadius: '20px',
        fontWeight: '500',
        display: 'flex',
        alignItems: 'center',
    }
    return (
        <div className='HeadPage'>
            <h3>{title}</h3>
            <div className='container_HeadPage'>
                {elements.map((value) => { return value; })}
                <div className='btn_support'>
                    <a className="btn btn-primary" type="button" style={btnPrimary}
                        href="https://api.whatsapp.com/send?phone=5493424416404&text=Hola Jorge, que tal? Me gustaría sacarme unas dudas sobre la plataforma GesPrender" target="_blank">
                        <img src="https://gesprender.com/app/template/assets/img/iconos/whatsapp.png" width="25px" />
                        Soporte Whatsapp
                    </a>
                </div>
            </div>
        </div>
    )
}

export function OfcanvasEnd(props) {
    const id = props.id;
    const title = props.title;
    const BodyOffcanvas = props.body ? props.body : '';
    const callback = props.callback;

    return (
        <div className="offcanvas offcanvas-end" id={id} aria-labelledby={id + "Label"}>
            <div className="offcanvas-header">
                <h5 id={id + "Label"}>{title}</h5>
                <button type="button" className="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div className="offcanvas-body">
                <BodyOffcanvas callback={callback} props={props} />
            </div>
        </div>
    );
}

// Buttons
export function ButtonOffcanvasEnd(props) {
    const id = props.id;
    const title = props.title;
    const BodyOffcanvas = props.body ? props.body : () => { return <div>No hay body aun</div> };
    const button = props.button;
    const callback = props.callback;
    return (
        <div className='ButtonOffcanvasEndButtons'>
            <button className="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target={'#' + id} aria-controls={id}>
                <img src="../template/assets/img/icons/mas.svg" width="20px" /> {button}
            </button>
            <div className="offcanvas offcanvas-end" id={id} aria-labelledby={id + "Label"}>
                <div className="offcanvas-header">
                    <h5 id={id + "Label"}>{title}</h5>
                    <button type="button" className="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div className="offcanvas-body">
                    <BodyOffcanvas callback={callback} props={props} />
                </div>
            </div>
        </div>
    );
}

export function HelpButton({funtionHandler}) {

    let styleButton = {
        display: 'flex',
        gap: '20px',
        marginBottom: '20px',
    }
    let btnPrimary = {
        background: '#fff',
        color: '#000',
        padding: '7px 16px',
        border: '#dddddd solid 1px',
        borderRadius: '20px',
        fontWeight: '500',
        display: 'flex',
        alignItems: 'center',
        gap: '5px'
    }
    return (
        <div style={styleButton} onClick={funtionHandler}>
            <a className="btn btn-primary" type="button" style={btnPrimary}>
                <img src="../template/assets/img/icons/icon_help.svg" width="25px" />
               Ayuda
            </a>
        </div>
    )
}


// Alerts
export function AlertInfo(props) {
    const message = props.message;
    return (
        <div className="alert alert-primary alert-dismissible fade show" role="alert">
            {message}
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    );
}

export function AlertWarning({ message }) {
    return (
        <div className="alert alert-warning alert-dismissible fade show" role="alert">
            {message}
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    );
}

// Setings
export const ViewMobileNotFound = () => {
    return (
        <div className="view_in_mobile m-2">
            <AlertWarning message="⚠ De momento no está disponible esta pantalla en dispositivos mobiles. Te recomendamos ingresar desde la Web." />
        </div>
    );
}