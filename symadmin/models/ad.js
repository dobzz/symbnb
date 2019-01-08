module.exports = (sequelize, DataTypes) => {
  const Model = sequelize.define('ad', {
    'title': {
      type: DataTypes.STRING,
    },
    'slug': {
      type: DataTypes.STRING,
    },
    'introduction': {
      type: DataTypes.STRING,
    },
    'content': {
      type: DataTypes.STRING,
    },
    'cover_image': {
      type: DataTypes.STRING,
    },
    'rooms': {
      type: DataTypes.INTEGER,
    },
  }, {
    tableName: 'ad',
    underscored: true,
    timestamps: false,
    
  });

  Model.associate = (models) => {
    Model.belongsTo(models.user, {
      foreignKey: 'author_id',
      
      as: '_author_id',
    });
    
  };

  return Model;
};

