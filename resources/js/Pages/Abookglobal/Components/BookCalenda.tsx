import { CustomTimeTable } from "../../Ahomeglobal/Components";
import { XMarkIcon } from "@heroicons/react/24/solid";
import { Button } from "@material-tailwind/react";
import { SelectedInfo } from '../../Ahomeglobal/Components/Room';

type RoomTimeProps = {
  dateSelected?: Date[],
  bookedDate?: string[],
  setDateSelected: (dates: Date[]) => void,
  onCheckout: () => void,
}

const BookCalenda = ({ dateSelected, setDateSelected, onCheckout, bookedDate = [] }: RoomTimeProps) => {
  // const booked = useRoomOrders();
  // const bookedDate = booked?.booked_dates || [];

  return (
    <div className='space-y-2 grid grid-col-1 md:grid-cols-3 gap-1 lg:gap-2'>
      <div className='space-y-1 md:col-span-2'>
        <CustomTimeTable
          selected={dateSelected}
          onChange={setDateSelected}
          minDate={new Date()}
          // maxDate={new Date(2025, 11, 19)}
          disable={bookedDate.map(s => new Date(s))}
          forcus={dateSelected?.length ? dateSelected[0] : undefined}
        ></CustomTimeTable>
      </div>
      <div>
        <span className='text-md font-semibold'>Đang chọn: {''}</span>
        <div className='col-span-1 flex flex-col space-y-1'>
          {!!dateSelected?.length && <div className='flex justify-between items-center my-2'>
            <span className='text-md font-semibold'>Thời gian lựa chọn:</span>
            <span className='bg-orange-400 rounded-full p-1' onClick={() => { setDateSelected([]); }}>
              <XMarkIcon className='size-4 text-white font-extrabold'></XMarkIcon>
            </span>
          </div>}
          <SelectedInfo dateSelected={dateSelected || []}></SelectedInfo>
          {/* @ts-ignore */}
          {!!dateSelected?.length && <Button placeholder={'view selected'} style={{ marginTop: '10px' }} onClick={onCheckout}>
            <span>Đặt ngay</span>
          </Button>}
        </div>
      </div>
    </div>
  )
}

export default BookCalenda;