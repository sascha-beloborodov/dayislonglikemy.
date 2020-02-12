import React, { Fragment } from 'react';
import styled from 'styled-components';

const CityItemStyled = styled.div`
    color: #000;
    cursor: pointer;
    padding: 5px 25px 5px 30px;
    &:hover {
        background: #eee
    }
`;

const CitiesListStyled = styled.div`
    position: absolute;
    z-index: 22;
    background: #fff;
`;

const CityList = ( { cities, chooseCity } ) => {
    return (<CitiesListStyled>
        {cities.map((c, idx) => {
            return <CityItemStyled key={idx} data-city-item={idx} onClick={() => chooseCity(c)}>{c.name} - {c.country_name}</CityItemStyled>
        })}
    </CitiesListStyled>);
};

export default CityList;
