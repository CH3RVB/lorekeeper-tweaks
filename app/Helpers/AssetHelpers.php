<?php

/*
|--------------------------------------------------------------------------
| Asset Helpers
|--------------------------------------------------------------------------
|
| These are used to manage asset arrays, which are used in keeping
| track of/distributing rewards.
|
*/

/**
 * Gets the asset keys for an array depending on whether the
 * assets being managed are owned by a user or character. 
 *
 * @param  bool  $isCharacter
 * @return array
 */
function getAssetKeys($isCharacter = false)
{
    if(!$isCharacter) return ['items', 'currencies', 'pets', 'raffle_tickets', 'loot_tables', 'user_items', 'characters'];
    else return ['currencies'];
}

/**
 * Gets the model name for an asset type.
 * The asset type has to correspond to one of the asset keys above. 
 *
 * @param  string  $type
 * @param  bool    $namespaced
 * @return string
 */
function getAssetModelString($type, $namespaced = true)
{
    switch($type)
    {
        case 'items':
            if($namespaced) return '\App\Models\Item\Item';
            else return 'Item';
            break;
        
        case 'currencies':
            if($namespaced) return '\App\Models\Currency\Currency';
            else return 'Currency';
            break;
        case 'pets':
            if($namespaced) return '\App\Models\Pet\Pet';
            else return 'Pet';
            break;
        case 'raffle_tickets':
            if($namespaced) return '\App\Models\Raffle\Raffle';
            else return 'Raffle';
            break;

        case 'loot_tables':
            if($namespaced) return '\App\Models\Loot\LootTable';
            else return 'LootTable';
            break;
            
        case 'user_items':
            if($namespaced) return '\App\Models\User\UserItem';
            else return 'UserItem';
            break;
            
        case 'characters':
            if($namespaced) return '\App\Models\Character\Character';
            else return 'Character';
            break;
    }
    return null;
}

/**
 * Initialises a new blank assets array, keyed by the asset type.
 *
 * @param  bool  $isCharacter
 * @return array
 */
function createAssetsArray($isCharacter = false)
{
    $keys = getAssetKeys($isCharacter);
    $assets = [];
    foreach($keys as $key) $assets[$key] = [];
    return $assets;
}

/**
 * Merges 2 asset arrays.
 *
 * @param  array  $first
 * @param  array  $second
 * @return array
 */
function mergeAssetsArrays($first, $second)
{
    $keys = getAssetKeys();
    foreach($keys as $key)
        foreach($second[$key] as $item)
            addAsset($first, $item['asset'], $item['quantity']);
    return $first;
}

/**
 * Adds an asset to the given array.
 * If the asset already exists, it adds to the quantity.
 *
 * @param  array  $array
 * @param  mixed  $asset
 * @param  int    $quantity
 */
function addAsset(&$array, $asset, $quantity = 1)
{
    if(!$asset) return;
    if(isset($array[$asset->assetType][$asset->id])) $array[$asset->assetType][$asset->id]['quantity'] += $quantity;
    else $array[$asset->assetType][$asset->id] = ['asset' => $asset, 'quantity' => $quantity];
}

/**
 * Get a clean version of the asset array to store in the database,
 * where each asset is listed in [id => quantity] format.
 * json_encode this and store in the data attribute.
 *
 * @param  array  $array
 * @param  bool   $isCharacter
 * @return array
 */
function getDataReadyAssets($array, $isCharacter = false)
{
    $result = [];
    foreach($array as $key => $type)
    {
        if($type && !isset($result[$key])) $result[$key] = [];
        foreach($type as $assetId => $assetData)
        {
            $result[$key][$assetId] = $assetData['quantity'];
        }
    }
    return $result;
}

/**
 * Retrieves the data associated with an asset array,
 * basically reversing the above function.
 * Use the data attribute after json_decode()ing it.
 *
 * @param  array  $array
 * @return array
 */
function parseAssetData($array)
{
    $assets = createAssetsArray();
    foreach($array as $key => $contents)
    {
        $model = getAssetModelString($key);
        if($model)
        {
            foreach($contents as $id => $quantity)
            {
                $assets[$key][$id] = [
                    'asset' => $model::find($id),
                    'quantity' => $quantity
                ];
            }

        }
    }
    return $assets;
}

/**
 * Distributes the assets in an assets array to the given recipient (user).
 * Loot tables will be rolled before distribution.
 *
 * @param  array                  $assets
 * @param  \App\Models\User\User  $sender
 * @param  \App\Models\User\User  $recipient
 * @param  string                 $logType
 * @param  string                 $data
 * @return array
 */
function fillUserAssets($assets, $sender, $recipient, $logType, $data)
{
    // Roll on any loot tables
    if(isset($assets['loot_tables']))
    {
        foreach($assets['loot_tables'] as $table)
        {
            $assets = mergeAssetsArrays($assets, $table['asset']->roll($table['quantity']));
        }
        unset($assets['loot_tables']);
    }

    foreach($assets as $key => $contents)
    {
        if($key == 'items' && count($contents))
        {
            $service = new \App\Services\InventoryManager;
            foreach($contents as $asset)
                if(!$service->creditItem($sender, $recipient, $logType, $data, $asset['asset'], $asset['quantity'])) return false;
        }
        elseif($key == 'currencies' && count($contents))
        {
            $service = new \App\Services\CurrencyManager;
            foreach($contents as $asset)
                if(!$service->creditCurrency($sender, $recipient, $logType, $data['data'], $asset['asset'], $asset['quantity'])) return false;
        }
        elseif($key == 'pets' && count($contents))
        {
            $service = new \App\Services\PetManager;
            foreach($contents as $asset)
                if(!$service->creditPet($sender, $recipient, $logType, $data, $asset['asset'], $asset['quantity'])) return false;
        }
        elseif($key == 'raffle_tickets' && count($contents))
        {
            $service = new \App\Services\RaffleManager;
            foreach($contents as $asset)
                if(!$service->addTicket($recipient, $asset['asset'], $asset['quantity'])) return false;
        }
        elseif($key == 'user_items' && count($contents))
        {
            $service = new \App\Services\InventoryManager;
            foreach($contents as $asset)
                if(!$service->moveStack($sender, $recipient, $logType, $data, $asset['asset'], $asset['quantity'])) return false;
        }
        elseif($key == 'characters' && count($contents))
        {
            $service = new \App\Services\CharacterManager;
            foreach($contents as $asset)
                if(!$service->moveCharacter($asset['asset'], $recipient, $data, $asset['quantity'], $logType)) return false;
        }
    }
    return $assets;
}

/**
 * Distributes the assets in an assets array to the given recipient (character).
 * Loot tables will be rolled before distribution.
 *
 * @param  array                            $assets
 * @param  \App\Models\User\User            $sender
 * @param  \App\Models\Character\Character  $recipient
 * @param  string                           $logType
 * @param  string                           $data
 * @return array
 */
function fillCharacterAssets($assets, $sender, $recipient, $logType, $data)
{
    foreach($assets as $key => $contents)
    {
        if($key == 'currencies' && count($contents))
        {
            $service = new \App\Services\CurrencyManager;
            foreach($contents as $asset)
                if(!$service->creditCurrency($sender, $recipient, $logType, $data['data'], $asset['asset'], $asset['quantity'])) return false;
        }
    }
    return true;
}