import React, { Fragment } from 'react';
import styled from 'styled-components';
import { headers } from './../config';
import axios from 'axios';

class City extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            src: null
        }
    }

    loadImage() {
        this.setState({
            loading: true
        });
        axios({
            url: `https://api.unsplash.com/search/photos?page=1&query=${this.props.city.name}`,
            method: 'get',
            headers: {
                ...headers,
                'Authorization': 'Client-ID 4968ec868a27af1beb33ae1f6573c9e0a8ee831e196246db76366eba3cc9882f'
            }
          })
            .then((res) => {
                if (res.data && res.data.results && res.data.results.length) {
                    this.setState({
                        src: res.data.results[0].urls.raw
                    });
                } else {
                    this.setState({
                        src: 'https://via.placeholder.com/1200x500'
                    });
                }
                this.setState({
                    loading: false
                });
            })
            .catch((err) => {
                console.log('ERROR:', err);
            });
    }

    componentDidMount() {
        this.loadImage();
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevProps.city.lng !== this.props.city.lng &&
            prevProps.city.lat !== this.props.city.lat)
        {
            this.loadImage();
        }
    }

    render() {
        return (
            <div className="card mb-3">
                {!this.state.loading ?
                     <img className="card-img-top" src={this.state.src} alt="Card image cap" />
                     : <span>Load image...</span>
                }
                <div className="card-body">
                    <h5 className="card-title">{this.props.city.name}</h5>
                    <p className="card-text">Sunrise: {this.props.city.sunrise}</p>
                    <p className="card-text">Sunset: {this.props.city.sunset}</p>
                    <p className="card-text"><small className="text-muted"></small></p>
                </div>
            </div>
            );
    }
    
};

export default City;
