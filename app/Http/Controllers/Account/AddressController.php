<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use AvoRed\Framework\Database\Models\Address;
use App\Http\Requests\Address\AddressRequest;
use AvoRed\Framework\Database\Repository\CountryRepository;
use AvoRed\Framework\Database\Contracts\CountryModelInterface;
use AvoRed\Framework\Database\Contracts\AddressModelInterface;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * @var \AvoRed\Framework\Database\Repository\CountryRepository $countryRepository
     */
    protected $countryRepository;

    /**
     * @var \AvoRed\Framework\Database\Repository\AddressRepository $addresRepository
     */
    protected $addressRepository;

    /**
     * Address Controller construct
     * @param \AvoRed\Framework\Database\Repository\CountryRepository $countryRepository
     * @param \AvoRed\Framework\Database\Repository\AddressRepository $addresRepository
     */
    public function __construct(
        CountryModelInterface $countryReposiry,
        AddressModelInterface $addressRepository
    ) {
        $this->countryRepository = $countryReposiry;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userAddresses = $this->addressRepository->getByUserId(Auth::user()->id);
        
        return view('account.address.index')
            ->with('userAddresses', $userAddresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeOptions = Address::TYPEOPTIONS;
        $countryOptions = $this->countryRepository->options();
        
        return view('account.address.create')
            ->with('countryOptions', $countryOptions)
            ->with('typeOptions', $typeOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Address\AddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);
        $this->addressRepository->create($request->all());
        return redirect()->route('account.address.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \AvoRed\Database\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        $typeOptions = Address::TYPEOPTIONS;
        $countryOptions = $this->countryRepository->options();

        return view('account.address.edit')
            ->with('address', $address)
            ->with('countryOptions', $countryOptions)
            ->with('typeOptions', $typeOptions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Address\AddressRequest  $request
     * @param \AvoRed\Database\Models\Address $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->all());

        return redirect()->route('account.address.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \AvoRed\Database\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        dd($address);
        $address->delete();

        return redirect()->route('account.address.index');
    }
}
