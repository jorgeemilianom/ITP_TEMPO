import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import { localData } from '../../hooks/CustomHooks';


const initialState = {
  data: [],
  reload: false
};

export const getData = createAsyncThunk("data/getData", async () => {
  let response = await localData({ getData: true });
  let json = await response.data;
  return json;

  //det som returneras här, kommer att bli vår action.payload
});

export const dataSlice = createSlice({
  name: '@data',
  initialState,
  reducers: {
    reloadData: (state, actions) => {
      
    },
  },
  extraReducers: {
    [getData.fulfilled]: (state, action) => {
      let updatedata = action.payload;
      state.data = updatedata;
    },
    [getData.pending]: (state) => {
      state.data = [];
    },
    [getData.rejected]: (state) => {
      state.data = [];
    }
  }
})

export const { reloadData } = dataSlice.actions;
export default dataSlice.reducer