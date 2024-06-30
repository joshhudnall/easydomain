<?php

namespace JoshHudnall\EasyDomain\Enums;

enum DomainComponent: string
{
    case Action = 'Action';
    case Adapter = 'Adapter';
    case Builder = 'Builder';
    case Cast = 'Cast';
    case Command = 'Command';
    case Contract = 'Contract';
    case DataTransferObject = 'DataTransferObject';
    case DomainModel = 'DomainModel';
    case Enum = 'Enum';
    case Event = 'Event';
    case Exception = 'Exception';
    case Factory = 'Factory';
    case Handler = 'Handler';
    case Job = 'Job';
    case Listener = 'Listener';
    case Middleware = 'Middleware';
    case Model = 'Model';
    case Observer = 'Observer';
    case Provider = 'Provider';
    case Request = 'Request';
    case Strategy = 'Strategy';
    case Trait = 'Trait';
    case Transformer = 'Transformer';
    case Validator = 'Validator';
    case ValueObject = 'ValueObject';
}
