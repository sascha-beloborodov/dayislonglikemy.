import React, { Fragment } from 'react';
import CityList from './components/CityList';
import City from './components/City';
import axios from 'axios';
import moment from 'moment';
import { headers } from './config';
import './App.css';

class App extends React.Component {

  constructor(props) {
    super(props);
    this.searchCity = this.searchCity.bind(this);
    this.chooseCity = this.chooseCity.bind(this);
    this.handleOutsideClick = this.handleOutsideClick.bind(this);
    this.state = {
      city: null,
      cityList: [],
      search: {
        city: null,
        date: null
      }
    };
  }

  chooseCity(city) {
    this.setState({
      city: city,
      cityList: []
    });
  }

  searchCity(e) {
    if (e.target.value.length < 3) {
      return;
    }
    document.removeEventListener('click', this.handleOutsideClick, false);
    const apiUrl = process.env.REACT_APP_API_URL;
    axios({
      url: `${apiUrl}search?q=${e.target.value}`,
      method: 'get',
      headers
    })
      .then((res) => {
        if (res.data && res.data.cities && res.data.cities.data && res.data.cities.data.length) {
          this.setState({
            cityList: res.data.cities.data
          });
          document.addEventListener('click', this.handleOutsideClick, false);
        }
      })
      .catch((err) => {
        console.log('ERROR:', err);
        document.removeEventListener('click', this.handleOutsideClick, false);
      });
  }

  handleOutsideClick(e) {
    // ignore clicks on the component itself
    if (e.target.getAttribute('data-city-item')) {
      return;
    }
    this.setState({
      cityList: []
    });
  }

  render () {
    return (
      <div className="App">
        <div className="row">
          <div className="col-md-8">
            <div className="form-group">
              <input type="text" className="form-control" onKeyUp={this.searchCity} />
              {this.state.cityList && <CityList cities={this.state.cityList} chooseCity={this.chooseCity} />}
            </div>
          </div>
          <div className="col-md-4">
          <input type="date" className="form-control" value="" />
          </div>
        </div>

        <div className="city-info">
          { this.state.city && <City city={this.state.city} /> }
        </div>
      </div>
    );
  }
}

export default App;
